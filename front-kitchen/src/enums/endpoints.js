
const hostMsKitchenUrl = import.meta.env.VITE_API_MS_KITCHEN_URL;
const hostMsPurchasesUrl = import.meta.env.VITE_API_MS_PURCHASES_URL;
const hostMsInventoriesUrl = import.meta.env.VITE_API_MS_INVENTORIES_URL;
/**
 * @description Enum with the endpoints of the API
 */
export default {
    getIngredients: hostMsInventoriesUrl+'ingredients',
    getRecipes: hostMsKitchenUrl+'kitchen/recipes',
    getOrders: hostMsKitchenUrl+'kitchen/orders/paginate',
    getOrdersPaginated: hostMsKitchenUrl+'kitchen/orders/paginate',
    getPurchases: hostMsPurchasesUrl+'purchases',
    prepareOrder: hostMsKitchenUrl+'kitchen/prepare',
}