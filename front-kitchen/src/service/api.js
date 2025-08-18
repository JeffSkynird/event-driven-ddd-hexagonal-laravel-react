import endpoints from "../enums/endpoints";

/**
 * @description Get ingredients paginated
 */
export const getIngredients = async ({queryKey}) => {
    const [_, page,perPage] = queryKey
    const response = await fetch(endpoints.getIngredients + `?page=${page+1}&perPage=${perPage}`);
    return response.json();
}
/**
 * @description Get recipes paginated
 */
export const getRecipes = async ({queryKey}) => {
    const [_, page,perPage] = queryKey
    const response = await fetch(endpoints.getRecipes + `?page=${page+1}&perPage=${perPage}`);
    return response.json();
}
/**
 * @description Get orders paginated
 */
export const getOrdersPaginated = async ({queryKey}) => {
    const [_, page,perPage] = queryKey
    const response = await fetch(endpoints.getOrdersPaginated + `?page=${page+1}&perPage=${perPage}`);
    return response.json();
}
/**
 * @description Get purchasesd paginated
 */
export const getPurchases = async ({queryKey}) => {
    console.log("queryKey", queryKey)
    const [_, page,perPage] = queryKey
    const response = await fetch(endpoints.getPurchases+ `?page=${page+1}&perPage=${perPage}`);
    return response.json();
}
/**
 * @description Prepare dish order
 */
export const prepareOrder = async (orderId) => {
    const response = await fetch(endpoints.prepareOrder, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({order_id:orderId})
    });
    return response.json();
}