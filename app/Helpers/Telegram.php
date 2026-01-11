<?php

namespace App\Helpers;

class Telegram {
	const LINK = 'https://t.me/lloid981_bot';

	public static function linkWithMessage(): string
	{
		return self::LINK . '?start=payload';
	}
}