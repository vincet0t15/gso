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
import typesRoutes from '@/routes/types';
import type { Type, TypeUpdateRequest } from '@/types/type';
import type { FormEvent } from 'react';

type EditTypeDialogProps = {
    isOpen: boolean;
    onClose: () => void;
    type: Type;
};

export function EditTypeDialog({
    isOpen,
    onClose,
    type,
}: EditTypeDialogProps) {
    const { data, setData, put, errors, processing } =
        useForm<TypeUpdateRequest>({
            name: type.name,
            description: type.description ?? '',
            is_active: type.is_active,
        });

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        put(typesRoutes.update(type).url, {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-md">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Edit Type</DialogTitle>
                        <DialogDescription>
                            Update the type details.
                        </DialogDescription>
                    </DialogHeader>

                    <div className="space-y-4">
                        <div className="space-y-2">
                            <Label htmlFor="name">Name *</Label>
                            <Input
                                id="name"
                                name="name"
                                value={data.name}
                                onChange={(event) =>
                                    setData('name', event.target.value)
                                }
                                required
                                autoFocus
                                placeholder="e.g., Office Supplies"
                            />
                            <InputError message={errors.name} />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="description">Description</Label>
                            <textarea
                                id="description"
                                name="description"
                                value={data.description ?? ''}
                                onChange={(event) =>
                                    setData('description', event.target.value)
                                }
                                placeholder="Optional description"
                                className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            />
                            <InputError message={errors.description} />
                        </div>

                        <div className="flex items-center space-x-2">
                            <input
                                id="is_active"
                                name="is_active"
                                type="checkbox"
                                checked={data.is_active ?? true}
                                onChange={(event) =>
                                    setData('is_active', event.target.checked)
                                }
                            />
                            <Label htmlFor="is_active">Active</Label>
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
                            Save Changes
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}