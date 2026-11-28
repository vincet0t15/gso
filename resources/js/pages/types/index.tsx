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
import typesRoutes from '@/routes/types';
import type { Type } from '@/types/type';
import type { PaginatedDataResponse } from '@/types/pagination';
import type { KeyboardEvent } from 'react';
import { CreateTypeDialog } from './create';
import { DeleteTypeDialog } from './delete';
import { EditTypeDialog } from './edit';

type TypesIndexProps = {
    types: PaginatedDataResponse<Type>;
    filters: {
        search?: string;
    };
};

export default function TypesIndex({ types, filters }: TypesIndexProps) {
    const { data, setData } = useForm({
        search: filters.search ?? '',
    });

    const [createOpen, setCreateOpen] = useState(false);
    const [selectedType, setSelectedType] = useState<Type | null>(null);
    const [editOpen, setEditOpen] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);

    const handleEdit = (type: Type) => {
        setSelectedType(type);
        setEditOpen(true);
    };

    const handleDelete = (type: Type) => {
        setSelectedType(type);
        setDeleteOpen(true);
    };

    const handleSearchKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();

        router.get(
            typesRoutes.index().url,
            data.search ? { search: data.search } : undefined,
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    return (
        <>
            <Head title="Types" />

            <div className="flex h-full min-w-0 flex-1 flex-col gap-4 rounded-xl p-4">
                <Heading
                    title="Type Management"
                    description="Create, update, and manage item types."
                />

                <div className="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <Can permission="types.create">
                        <Button onClick={() => setCreateOpen(true)}>
                            <Plus />
                            Create Type
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
                            onChange={(event) =>
                                setData('search', event.target.value)
                            }
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
                                <TableHead>Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead className="text-right">
                                    Actions
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {types.data.length > 0 ? (
                                types.data.map((type) => (
                                    <TableRow key={type.id}>
                                        <TableCell className="font-medium">
                                            {type.name}
                                        </TableCell>
                                        <TableCell>
                                            {type.description || (
                                                <span className="text-muted-foreground">
                                                    —
                                                </span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {type.is_active ? 'Active' : 'Inactive'}
                                        </TableCell>
                                        <TableCell className="flex items-center justify-end gap-2">
                                            <Can permission="types.update">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-8 w-8 text-green-600 hover:bg-green-50 hover:text-green-700"
                                                    onClick={() =>
                                                        handleEdit(type)
                                                    }
                                                    title="Edit type"
                                                >
                                                    <Pencil />
                                                </Button>
                                            </Can>
                                            <Can permission="types.delete">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-8 w-8 text-red-600 hover:bg-red-50 hover:text-red-700"
                                                    onClick={() =>
                                                        handleDelete(type)
                                                    }
                                                    title="Delete type"
                                                >
                                                    <Trash2 />
                                                </Button>
                                            </Can>
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={4}
                                        className="h-24 text-center text-muted-foreground"
                                    >
                                        No types found.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>

                <Pagination data={types} />

                {createOpen && (
                    <CreateTypeDialog
                        isOpen={createOpen}
                        onClose={() => setCreateOpen(false)}
                    />
                )}

                {editOpen && selectedType && (
                    <EditTypeDialog
                        isOpen={editOpen}
                        onClose={() => setEditOpen(false)}
                        type={selectedType}
                    />
                )}

                {deleteOpen && selectedType && (
                    <DeleteTypeDialog
                        isOpen={deleteOpen}
                        onClose={() => setDeleteOpen(false)}
                        type={selectedType}
                    />
                )}
            </div>
        </>
    );
}

TypesIndex.layout = {
    breadcrumbs: [
        {
            title: 'Types',
            href: typesRoutes.index().url,
        },
    ],
};