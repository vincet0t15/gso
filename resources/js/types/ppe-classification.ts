export type PpeClassification = {
    id: number;
    code: string;
    name: string;
    description: string | null;
    is_active: boolean;
};

export type PpeClassificationCreateRequest = {
    code: string;
    name: string;
    description?: string | null;
    is_active?: boolean;
};

export type PpeClassificationUpdateRequest = Partial<PpeClassificationCreateRequest>;