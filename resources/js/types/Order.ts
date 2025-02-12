export type OrderItem = {
    id: number;
    product: {
        id: number;
        name: string;
    };
    quantity: number;
    price: number;
    subtotal: number;
};

export type Order = {
    id: number;
    user_id: number;
    customer_name: string;
    total: number;
    status: 'pending' | 'processing' | 'shipped' | 'delivered' | 'canceled';
    payment_status: 'paid' | 'unpaid' | 'failed';
    payment_method: string | null;
    placed_at: string | null;
    items: OrderItem[];
};
