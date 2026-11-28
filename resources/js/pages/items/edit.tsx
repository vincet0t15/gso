import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import itemsRoutes from '@/routes/items';
import type { Item, ItemUpdateRequest } from '@/types/item';
import type { FormEvent } from 'react';

type Option = { id: number; name: string; code?: string };

type Props = {
    isOpen: boolean;
    onClose: () => void;
    item: Item;
    types: Option[];
    ppeClassifications: Option[];
    units: Option[];
};

const selectClass =
    'flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50';

export function EditItemDialog({ isOpen, onClose, item, types, ppeClassifications, units }: Props) {
    const { data, setData, put, errors, processing } =
        useForm<ItemUpdateRequest & { image?: File | null; remove_image?: boolean }>({
            item_code: item.item_code,
            item_name: item.item_name,
            description: item.description ?? '',
            type_id: item.type_id,
            ppe_classification_id: item.ppe_classification_id,
            unit_id: item.unit_id,
            unit_cost: Number(item.unit_cost),
            is_active: item.is_active,
            image: null,
            remove_image: false,
        });

    const [preview, setPreview] = useState<string | null>(item.image_url ?? null);

    const handleImageChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0] ?? null;
        setData('image', file);
        if (file) {
            setPreview(URL.createObjectURL(file));
            setData('remove_image', false);
        }
    };

    const handleRemoveImage = () => {
        setData('image', null);
        setData('remove_image', true);
        setPreview(null);
    };

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        put(itemsRoutes.update(item).url, {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Edit Item</DialogTitle>
                        <DialogDescription>
                            Update the item details.
                        </DialogDescription>
                    </DialogHeader>

                    <div className="space-y-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="item_code">Item Code *</Label>
                                <Input
                                    id="item_code"
                                    name="item_code"
                                    value={data.item_code}
                                    onChange={(event) => setData('item_code', event.target.value)}
                                    required
                                    autoFocus
                                />
                                <InputError message={errors.item_code} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="item_name">Item Name *</Label>
                                <Input
                                    id="item_name"
                                    name="item_name"
                                    value={data.item_name}
                                    onChange={(event) => setData('item_name', event.target.value)}
                                    required
                                />
                                <InputError message={errors.item_name} />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="description">Description</Label>
                            <textarea
                                id="description"
                                name="description"
                                value={data.description ?? ''}
                                onChange={(event) => setData('description', event.target.value)}
                                placeholder="Optional description"
                                className="flex min-h-[60px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            />
                            <InputError message={errors.description} />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="type_id">Type</Label>
                                <select
                                    id="type_id"
                                    name="type_id"
                                    value={data.type_id ?? ''}
                                    onChange={(event) =>
                                        setData('type_id', event.target.value ? Number(event.target.value) : null)
                                    }
                                    className={selectClass}
                                >
                                    <option value="">— None —</option>
                                    {types.map((t) => (
                                        <option key={t.id} value={t.id}>
                                            {t.name}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.type_id} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="ppe_classification_id">PPE Classification</Label>
                                <select
                                    id="ppe_classification_id"
                                    name="ppe_classification_id"
                                    value={data.ppe_classification_id ?? ''}
                                    onChange={(event) =>
                                        setData('ppe_classification_id', event.target.value ? Number(event.target.value) : null)
                                    }
                                    className={selectClass}
                                >
                                    <option value="">— None —</option>
                                    {ppeClassifications.map((p) => (
                                        <option key={p.id} value={p.id}>
                                            {p.name}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.ppe_classification_id} />
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="unit_id">Unit of Measure</Label>
                                <select
                                    id="unit_id"
                                    name="unit_id"
                                    value={data.unit_id ?? ''}
                                    onChange={(event) =>
                                        setData('unit_id', event.target.value ? Number(event.target.value) : null)
                                    }
                                    className={selectClass}
                                >
                                    <option value="">— None —</option>
                                    {units.map((u) => (
                                        <option key={u.id} value={u.id}>
                                            {u.name} ({u.code})
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.unit_id} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="unit_cost">Unit Cost</Label>
                                <Input
                                    id="unit_cost"
                                    name="unit_cost"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={data.unit_cost ?? 0}
                                    onChange={(event) =>
                                        setData('unit_cost', event.target.value ? Number(event.target.value) : 0)
                                    }
                                    placeholder="0.00"
                                />
                                <InputError message={errors.unit_cost} />
                            </div>
                        </div>

                        <div className="flex items-center space-x-2">
                            <input
                                id="is_active"
                                name="is_active"
                                type="checkbox"
                                checked={data.is_active ?? true}
                                onChange={(event) => setData('is_active', event.target.checked)}
                            />
                            <Label htmlFor="is_active">Active</Label>
                        </div>
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="image">Image</Label>
                        {preview && (
                            <img
                                src={preview}
                                alt="Preview"
                                className="mb-2 h-32 w-32 rounded-md object-cover border"
                            />
                        )}
                        <Input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/*"
                            onChange={handleImageChange}
                        />
                        {preview && (
                            <Button type="button" variant="outline" size="sm" onClick={handleRemoveImage}>
                                Remove Image
                            </Button>
                        )}
                        <InputError message={errors.image} />
                    </div>

                    <DialogFooter className="mt-4">
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner />}
                            Save Changes
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}