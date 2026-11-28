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
import unitsRoutes from '@/routes/units';
import type { Unit } from '@/types/unit';
import type { PaginatedDataResponse } from '@/types/pagination';
import type { KeyboardEvent } from 'react';
import { CreateUnitDialog } from './create';
import { DeleteUnitDialog } from './delete';
import { EditUnitDialog } from './edit';

type Props = {
    units: PaginatedDataResponse<Unit>;
    filters: { search?: string };
};

export default function UnitsIndex({ units, filters }: Props) {
    const { data, setData } = useForm({ search: filters.search ?? '' });

    const [createOpen, setCreateOpen] = useState(false);
    const [selected, setSelected] = useState<Unit | null>(null);
    const [editOpen, setEditOpen] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);

    const handleEdit = (item: Unit) => {
        setSelected(item);
        setEditOpen(true);
    };

    const handleDelete = (item: Unit) => {
        setSelected(item);
        setDeleteOpen(true);
    };

    const handleSearchKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        router.get(
            unitsRoutes.index().url,
            data.search ? { search: data.search } : undefined,
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <>
            <Head title="Units of Measure" />

            <div className="flex h-full min-w-0 flex-1 flex-col gap-4 rounded-xl p-4">
                <Heading
                    title="Unit of Measure Management"
                    description="Create, update, and manage units of measure."
                />

                <div className="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <Can permission="units.create">
                        <Button onClick={() => setCreateOpen(true)}>
                            <Plus />
                            Create Unit
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
                            {units.data.length > 0 ? (
                                units.data.map((item) => (
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
                                            <Can permission="units.update">
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
                                            <Can permission="units.delete">
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
                                        No units found.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>

                <Pagination data={units} />

                {createOpen && (
                    <CreateUnitDialog isOpen={createOpen} onClose={() => setCreateOpen(false)} />
                )}

                {editOpen && selected && (
                    <EditUnitDialog isOpen={editOpen} onClose={() => setEditOpen(false)} unit={selected} />
                )}

                {deleteOpen && selected && (
                    <DeleteUnitDialog isOpen={deleteOpen} onClose={() => setDeleteOpen(false)} unit={selected} />
                )}
            </div>
        </>
    );
}

UnitsIndex.layout = {
    breadcrumbs: [
        {
            title: 'Units of Measure',
            href: unitsRoutes.index().url,
        },
    ],
};