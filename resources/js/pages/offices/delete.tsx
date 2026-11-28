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
import officesRoutes from '@/routes/offices';
import type { Office } from '@/types/office';
import type { FormEvent } from 'react';

type DeleteOfficeDialogProps = {
    isOpen: boolean;
    onClose: () => void;
    office: Office;
};

export function DeleteOfficeDialog({
    isOpen,
    onClose,
    office,
}: DeleteOfficeDialogProps) {
    const { delete: destroy, processing } = useForm({});

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        destroy(officesRoutes.destroy(office).url, {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-md">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Delete Office</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete "{office.office_name}"?
                            This action cannot be undone.
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