export const formatPrice = (value, currency = 'lei') => `${Number(value ?? 0).toFixed(2)} ${currency}`

export const lineTotal = (product) => parseFloat(product.price) * (product.pivot?.count ?? 1)

export const orderTotal = (order) => (order.products ?? []).reduce((sum, p) => sum + lineTotal(p), 0)
