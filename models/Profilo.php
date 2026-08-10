<?php

namespace app\models;

/**
 * @property int    $idprofile
 * @property string $fullname
 * @property string $nome
 * @property string $cognome
 * @property string $comune
 * @property int    $user_id
 * @property string $telefono
 * @property string $cellulare
 * @property string $ruolo
 * @property string $bio
 * @property string $avatar
 */
class Profilo extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'profile';
    }

    public function rules(): array
    {
        return [
            [['fullname', 'comune', 'nome', 'cognome', 'telefono', 'cellulare', 'ruolo', 'bio'], 'string'],
            ['fullname', 'string', 'max' => 45],
            [['nome', 'cognome', 'ruolo'], 'string', 'max' => 100],
            [['telefono', 'cellulare'], 'string', 'max' => 30],
            ['comune', 'string', 'max' => 45],
            ['avatar', 'string', 'max' => 255],
            ['user_id', 'integer'],
            [['fullname', 'comune', 'nome', 'cognome', 'telefono', 'cellulare', 'ruolo', 'bio', 'avatar', 'user_id'], 'safe'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fullname'  => 'Nome Completo',
            'nome'      => 'Nome',
            'cognome'   => 'Cognome',
            'comune'    => 'Comune',
            'telefono'  => 'Telefono',
            'cellulare' => 'Cellulare',
            'ruolo'     => 'Ruolo / Qualifica',
            'bio'       => 'Note / Bio',
        ];
    }

    public static function findByUserId(int $userId): ?self
    {
        return static::findOne(['user_id' => $userId]);
    }

    public static function findOrCreate(int $userId): self
    {
        $p = static::findOne(['user_id' => $userId]);
        if (!$p) {
            $p = new self(['user_id' => $userId]);
        }
        return $p;
    }

    /** Returns initials (1 or 2 chars) for avatar generation. */
    public function getInitials(): string
    {
        if ($this->nome && $this->cognome) {
            return mb_strtoupper(mb_substr($this->nome, 0, 1) . mb_substr($this->cognome, 0, 1));
        }
        if ($this->fullname) {
            $parts = explode(' ', trim($this->fullname));
            if (count($parts) >= 2) {
                return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
            }
            return mb_strtoupper(mb_substr($this->fullname, 0, 2));
        }
        return '??';
    }

    /** Returns display name (fullname or nome+cognome). */
    public function getDisplayName(): string
    {
        if ($this->nome || $this->cognome) {
            return trim($this->nome . ' ' . $this->cognome);
        }
        return $this->fullname ?? '';
    }

    public function getAvatarPath(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        $path = Yii::getAlias('@webroot') . '/uploads/avatars/' . $this->avatar;
        return file_exists($path) ? $path : null;
    }
}
