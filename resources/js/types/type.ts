export type Type = {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
};

export type TypeCreateRequest = {
    name: string;
    description?: string | null;
    is_active?: boolean;
};

export type TypeUpdateRequest = Partial<TypeCreateRequest>;