# Old Discord bot
This was intially made for Naver's LINE when they allowed non-commercial entities to manage bots that could embed media links (images) through webhooks. This is that same bot but rewritten for Discord and made to use less than 5MB ram so it runs on a potato vps.

This uses [DiscordPHP](https://github.com/discord-php/DiscordPHP) and SQLite3 to keep under 5MB ram while running.


usage:

```bash
php composer.phar update
nohup php markov.php >/dev/null 2>&1 &
```
