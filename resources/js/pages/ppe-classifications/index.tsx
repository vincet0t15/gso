import { Head, router, useForm } from '@inertiajs/react';
import { Pencil, Plus, Search, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Can } from '@/components/can';
import Heading from '@/components/heading';
import Pagination from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import ppeClassificationsRoutes from '@/routes/ppe-classifications';
import type { PpeClassification } from '@/types/ppe-classification';
import type { PaginatedDataResponse } from '@/types/pagination';
import type { KeyboardEvent } from 'react';
import { CreatePpeClassificationDialog } from './create';
import { DeletePpeClassificationDialog } from './delete';
import { EditPpeClassificationDialog } from './edit';

type Props = {
    ppeClassifications: PaginatedDataResponse<PpeClassification>;
    filters: { search?: string };
};

export default function PpeClassificationsIndex({ ppeClassifications, filters }: Props) {
    const { data, setData } = useForm({ search: filters.search ?? '' });

    const [createOpen, setCreateOpen] = useState(false);
    const [selected, setSelected] = useState<PpeClassification | null>(null);
    const [editOpen, setEditOpen] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);

    const handleEdit = (item: PpeClassification) => {
        setSelected(item);
        setEditOpen(true);
    };

    const handleDelete = (item: PpeClassification) => {
        setSelected(item);
        setDeleteOpen(true);
    };

    const handleSearchKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        router.get(
            ppeClassificationsRoutes.index().url,
            data.search ? { search: data.search } : undefined,
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <>
            <Head title="PPE Classifications" />

            <div className="flex h-full min-w-0 flex-1 flex-col gap-4 rounded-xl p-4">
                <Heading
                    title="PPE Classification Management"
                    description="Create, update, and manage PPE classifications."
                />

                <div className="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <Can permission="ppe-classifications.create">
                        <Button onClick={() => setCreateOpen(true)}>
                            <Plus />
                            Create PPE Classification
                        </Button>
                    </Can>

                    <div className="relative w-full sm:w-[250px]">
                        <Label htmlFor="search" className="sr-only">
                            Search
                        </Label>
                        <Input
                            id="search"
                            className="w-full pl-8"
                            value={data.search}
                            onChange={(event) => setData('search', event.target.value)}
                            onKeyDown={handleSearchKeyDown}
                            placeholder="Search..."
                        />
                        <Search className="pointer-events-none absolute top-1/2 left-2 size-4 -translate-y-1/2 opacity-50 select-none" />
                    </div>
                </div>

                <div className="w-full overflow-hidden rounded-sm border shadow-sm">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Code</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {ppeClassifications.data.length > 0 ? (
                                ppeClassifications.data.map((item) => (
                                    <TableRow key={item.id}>
                                        <TableCell className="font-medium">
                                            {item.code}
                                        </TableCell>
                                        <TableCell>{item.name}</TableCell>
                                        <TableCell>
                                            {item.description || (
                                                <span className="text-muted-foreground">—</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {item.is_active ? 'Active' : 'Inactive'}
                                        </TableCell>
                                        <TableCell className="flex items-center justify-end gap-2">
                                            <Can permission="ppe-classifications.update">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-8 w-8 text-green-600 hover:bg-green-50 hover:text-green-700"
                                                    onClick={() => handleEdit(item)}
                                                    title="Edit"
                                                >
                                                    <Pencil />
                                                </Button>
                                            </Can>
                                            <Can permission="ppe-classifications.delete">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-8 w-8 text-red-600 hover:bg-red-50 hover:text-red-700"
                                                    onClick={() => handleDelete(item)}
                                                    title="Delete"
                                                >
                                                    <Trash2 />
                                                </Button>
                                            </Can>
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell colSpan={5} className="h-24 text-center text-muted-foreground">
                                        No PPE classifications found.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>

                <Pagination data={ppeClassifications} />

                {createOpen && (
                    <CreatePpeClassificationDialog isOpen={createOpen} onClose={() => setCreateOpen(false)} />
                )}

                {editOpen && selected && (
                    <EditPpeClassificationDialog
                        isOpen={editOpen}
                        onClose={() => setEditOpen(false)}
                        ppeClassification={selected}
                    />
                )}

                {deleteOpen && selected && (
                    <DeletePpeClassificationDialog
                        isOpen={deleteOpen}
                        onClose={() => setDeleteOpen(false)}
                        ppeClassification={selected}
                    />
                )}
            </div>
        </>
    );
}

PpeClassificationsIndex.layout = {
    breadcrumbs: [
        {
            title: 'PPE Classifications',
            href: ppeClassificationsRoutes.index().url,
        },
    ],
};