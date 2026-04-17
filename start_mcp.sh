#!/bin/bash
LOG=/var/log/utcbim-mcp.log
echo "[$(date)] Avvio MCP server..." >> "$LOG"
exec /var/www/hubapi_env/bin/python /var/www/utcbim/mcp_server.py --transport sse >> "$LOG" 2>&1
