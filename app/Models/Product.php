<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Représentation d'un produit",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Laptop"),
 *     @OA\Property(property="description", type="string", example="Ordinateur performant"),
 *     @OA\Property(property="price", type="number", format="float", example=899.99),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'stock'];
    protected $casts = [
        'price' => 'float'
    ];
}
