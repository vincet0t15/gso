import { useForm } from '@inertiajs/react';
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
import type { ItemCreateRequest } from '@/types/item';
import type { FormEvent } from 'react';

type Option = { id: number; name: string; code?: string };

type Props = {
    isOpen: boolean;
    onClose: () => void;
    types: Option[];
    ppeClassifications: Option[];
    units: Option[];
};

const selectClass =
    'flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50';

export function CreateItemDialog({ isOpen, onClose, types, ppeClassifications, units }: Props) {
    const { data, setData, post, reset, errors, processing } =
        useForm<ItemCreateRequest>({
            item_code: '',
            item_name: '',
            description: '',
            type_id: null,
            ppe_classification_id: null,
            unit_id: null,
            unit_cost: 0,
            reorder_point: 0,
            is_active: true,
        });

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(itemsRoutes.store().url, {
            onSuccess: () => {
                onClose();
                reset();
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Create Item</DialogTitle>
                        <DialogDescription>
                            Add a new item.
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
                                    placeholder="e.g., ITM-0001"
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
                                    placeholder="e.g., Bond Paper A4"
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

                        <div className="space-y-2">
                            <Label htmlFor="reorder_point">Reorder Point</Label>
                            <Input
                                id="reorder_point"
                                name="reorder_point"
                                type="number"
                                min="0"
                                value={data.reorder_point ?? 0}
                                onChange={(event) =>
                                    setData('reorder_point', event.target.value ? Number(event.target.value) : 0)
                                }
                                placeholder="0"
                            />
                            <InputError message={errors.reorder_point} />
                        </div>
                    </div>

                    <DialogFooter className="mt-4">
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner />}
                            Create Item
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}