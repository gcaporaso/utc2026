<?php

namespace app\controllers;

use app\models\Profilo;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfiloController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => ['avatar'], 'allow' => true, 'roles' => ['?', '@']],
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $profilo = Profilo::findOrCreate(Yii::$app->user->id);
        $user    = Yii::$app->user->identity;
        return $this->render('index', compact('profilo', 'user'));
    }

    public function actionSave(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $profilo = Profilo::findOrCreate(Yii::$app->user->id);
        $post    = Yii::$app->request->post();

        $profilo->nome      = $post['nome']      ?? $profilo->nome;
        $profilo->cognome   = $post['cognome']   ?? $profilo->cognome;
        $profilo->fullname  = trim(($post['nome'] ?? '') . ' ' . ($post['cognome'] ?? '')) ?: $profilo->fullname;
        $profilo->ruolo     = $post['ruolo']     ?? $profilo->ruolo;
        $profilo->telefono  = $post['telefono']  ?? $profilo->telefono;
        $profilo->cellulare = $post['cellulare'] ?? $profilo->cellulare;
        $profilo->bio       = $post['bio']       ?? $profilo->bio;

        if ($profilo->save()) {
            return ['ok' => true];
        }
        return ['ok' => false, 'error' => implode('; ', $profilo->firstErrors)];
    }

    /** Handles avatar upload via AJAX multipart POST. */
    public function actionUploadAvatar(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName('avatar');
        if (!$file) {
            return ['ok' => false, 'error' => 'Nessun file ricevuto.'];
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($file->extension), $allowed, true)) {
            return ['ok' => false, 'error' => 'Formato non supportato. Usa JPG, PNG, GIF o WEBP.'];
        }
        if ($file->size > 2 * 1024 * 1024) {
            return ['ok' => false, 'error' => 'Il file non deve superare 2 MB.'];
        }

        $userId   = Yii::$app->user->id;
        $filename = $userId . '_' . time() . '.' . strtolower($file->extension);
        $dir      = Yii::getAlias('@webroot') . '/uploads/avatars/';

        // Remove old avatar files for this user
        foreach (glob($dir . $userId . '_*') as $old) {
            @unlink($old);
        }

        if (!$file->saveAs($dir . $filename)) {
            return ['ok' => false, 'error' => 'Salvataggio file non riuscito.'];
        }

        $profilo         = Profilo::findOrCreate($userId);
        $profilo->avatar = $filename;
        $profilo->save();

        return ['ok' => true, 'url' => Yii::$app->request->baseUrl . '/uploads/avatars/' . $filename];
    }

    /** Serves the avatar for a given user_id (or the logged-in user). */
    public function actionAvatar(?int $userId = null): Response
    {
        $userId  = $userId ?? (Yii::$app->user->isGuest ? 0 : (int)Yii::$app->user->id);
        $profilo = $userId ? Profilo::findOne(['user_id' => $userId]) : null;

        if ($profilo && $profilo->avatar) {
            $path = Yii::getAlias('@webroot') . '/uploads/avatars/' . $profilo->avatar;
            if (file_exists($path)) {
                return Yii::$app->response->sendFile($path, $profilo->avatar, ['inline' => true]);
            }
        }

        // Generate SVG avatar from initials
        $initials = $profilo ? $profilo->getInitials() : $this->initialsFromUsername($userId);
        $color    = $this->colorFromId($userId);

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'image/svg+xml');
        Yii::$app->response->headers->set('Cache-Control', 'public, max-age=300');

        return $this->generateSvgAvatar($initials, $color);
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    private function initialsFromUsername(int $userId): string
    {
        $user = \mdm\admin\models\User::findOne($userId);
        if ($user) {
            $parts = explode(' ', trim($user->username));
            if (count($parts) >= 2) {
                return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
            }
            return mb_strtoupper(mb_substr($user->username, 0, 2));
        }
        return '?';
    }

    private function colorFromId(int $userId): string
    {
        $palette = ['#3a7bd5', '#2ecc71', '#e74c3c', '#8e44ad', '#f39c12', '#16a085', '#d35400', '#2980b9'];
        return $palette[$userId % count($palette)];
    }

    private function generateSvgAvatar(string $initials, string $color): Response
    {
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
  <circle cx="80" cy="80" r="80" fill="{$color}"/>
  <text x="80" y="80" dy=".35em" text-anchor="middle"
        fill="white" font-family="Arial, Helvetica, sans-serif"
        font-size="62" font-weight="700">{$initials}</text>
</svg>
SVG;
        Yii::$app->response->content = $svg;
        return Yii::$app->response;
    }
}
