export type Item = {
    id: number;
    item_code: string;
    item_name: string;
    description: string | null;
    type_id: number | null;
    type?: { id: number; name: string } | null;
    ppe_classification_id: number | null;
    ppeClassification?: { id: number; name: string } | null;
    unit_id: number | null;
    unit?: { id: number; name: string; code: string } | null;
    unit_cost: string | number;
    reorder_point: number;
    is_active: boolean;
};

export type ItemCreateRequest = {
    item_code: string;
    item_name: string;
    description?: string | null;
    type_id?: number | null;
    ppe_classification_id?: number | null;
    unit_id?: number | null;
    unit_cost?: number | null;
    reorder_point?: number | null;
    is_active?: boolean;
};

export type ItemUpdateRequest = Partial<ItemCreateRequest>;

export type SelectOption = {
    id: number;
    name: string;
};