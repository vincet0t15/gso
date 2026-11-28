export type Unit = {
    id: number;
    code: string;
    name: string;
    description: string | null;
    is_active: boolean;
};

export type UnitCreateRequest = {
    code: string;
    name: string;
    description?: string | null;
    is_active?: boolean;
};

export type UnitUpdateRequest = Partial<UnitCreateRequest>;