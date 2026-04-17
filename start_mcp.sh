#!/bin/bash
LOG=/var/www/utcbim/mcp_server.log

# Termina eventuale istanza precedente
pkill -f mcp_server.py 2>/dev/null
sleep 1

echo "[$(date)] Avvio MCP server..." >> "$LOG"
exec /var/www/hubapi_env/bin/python /var/www/utcbim/mcp_server.py --transport sse >> "$LOG" 2>&1
