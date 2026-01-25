<?php

namespace App\Helpers;

class UserStatus
{
	const ACTIVE = "ACTIVE";
	const SUSPENDED = "SUSPENDED";
	const BANNED = "BANNED";
}

class PembayaranStatus
{
	const MENUNGGU = 'MENUNGGU';
	const DITERIMA = 'DITERIMA';
	const DITOLAK = 'DITOLAK';
}

class TransaksiStatus
{
	const BELUMBAYAR = 'BELUMBAYAR';
	const PENDING = 'PENDING';
	const SUKSES = 'SUKSES';
	const GAGAL = 'GAGAL';
	const DIKIRIM = 'DIKIRIM';
}

class CartStatus
{
	const AKTIF = 'AKTIF';
	const CHECKOUT = 'CHECKOUT';
	const DIBATALKAN = 'DIBATALKAN';
}

class NikVerified
{
	const EMPTY = 'EMPTY';
	const APPROVED = 'APPROVED';
	const PENDING = 'PENDING';
	const REJECTED = 'REJECTED';
}

class KeranjangStatus
{
	const AKTIF = 'AKTIF';
	const CHECKOUT = 'CHECKOUT';
	const DIBATALKAN = 'DIBATALKAN';
}
