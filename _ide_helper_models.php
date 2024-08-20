<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $currency
 * @property string $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ExchangeRateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereUpdatedAt($value)
 */
	class ExchangeRate extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $store_id
 * @property string $purshase_date
 * @property string $sum
 * @property string $currency
 * @property string|null $document_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Database\Factories\PurshaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase query()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereDocumentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase wherePurshaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereUpdatedAt($value)
 */
	class Purshase extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Purshase> $purshases
 * @property-read int|null $purshases_count
 * @method static \Database\Factories\StoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 */
	class Store extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

