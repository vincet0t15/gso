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
import typesRoutes from '@/routes/types';
import type { Type } from '@/types/type';
import type { FormEvent } from 'react';

type DeleteTypeDialogProps = {
    isOpen: boolean;
    onClose: () => void;
    type: Type;
};

export function DeleteTypeDialog({
    isOpen,
    onClose,
    type,
}: DeleteTypeDialogProps) {
    const { delete: destroy, processing } = useForm({});

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        destroy(typesRoutes.destroy(type).url, {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-md">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Delete Type</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete "{type.name}"? This
                            action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter className="mt-4">
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button
                            type="submit"
                            variant="destructive"
                            disabled={processing}
                        >
                            {processing && <Spinner />}
                            Delete
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}