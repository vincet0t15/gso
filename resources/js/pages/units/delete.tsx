import { useForm } from '@inertiajs/react';
import { Spinner } from '@/components/ui/spinner';
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
import unitsRoutes from '@/routes/units';
import type { Unit } from '@/types/unit';
import type { FormEvent } from 'react';

type Props = { isOpen: boolean; onClose: () => void; unit: Unit };

export function DeleteUnitDialog({ isOpen, onClose, unit }: Props) {
    const { delete: destroy, processing } = useForm({});

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        destroy(unitsRoutes.destroy(unit).url, {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-md">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Delete Unit of Measure</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete "{unit.name}"? This action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter className="mt-4">
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" variant="destructive" disabled={processing}>
                            {processing && <Spinner />}
                            Delete
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}