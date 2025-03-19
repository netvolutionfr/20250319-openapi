<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info (
 *     title="Laravel API",
 *     version="1.0.0",
 *     @OA\Contact (
 *     email="stanis@netvolution.fr",
 *     name="Stanis"
 *    ),
 *     @OA\License (
 *     name="MIT",
 *     url="https://opensource.org/licenses/MIT"
 *   )
 * )
 * @OA\PathItem(
 *      path="/api/products",
 *      description="Opérations sur les produits",
 *      summary="Gestion des produits",
 *  )
 *
 * @OA\Tag(
 *      name="Produits",
 *      description="API pour gérer les produits"
 *  )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Liste tous les produits",
     *     description="Retourne une liste de tous les produits",
     *     tags={"Produits"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products)->setStatusCode(200);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Créer un produit",
     *     description="Ajoute un nouveau produit dans la base de données",
     *     tags={"Produits"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "stock"},
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="description", type="string", example="Ordinateur performant"),
     *             @OA\Property(property="price", type="number", format="float", example=899.99),
     *             @OA\Property(property="stock", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer']
        ]);

        $product = Product::create($validated);
        return response()->json(['message' => 'Product created', 'product' => $product])->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Afficher un produit spécifique",
     *     description="Récupère les détails d'un produit via son ID",
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du produit",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'])->setStatusCode(404);
        }
        return response()->json($product)->setStatusCode(200);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Mettre à jour un produit",
     *     description="Met à jour les informations d'un produit existant",
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Laptop Pro"),
     *             @OA\Property(property="description", type="string", example="Ordinateur portable haut de gamme"),
     *             @OA\Property(property="price", type="number", format="float", example=1199.99),
     *             @OA\Property(property="stock", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer']
        ]);

        $product->update($validated);
        return response()->json(['message' => 'Product updated', 'product' => $product])->setStatusCode(200);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Supprimer un produit",
     *     description="Supprime un produit existant via son ID",
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=204, description="Produit supprimé avec succès"),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'])->setStatusCode(404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted'])->setStatusCode(200);
    }
}
