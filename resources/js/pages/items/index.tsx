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
import itemsRoutes from '@/routes/items';
import type { Item } from '@/types/item';
import type { PaginatedDataResponse } from '@/types/pagination';
import type { KeyboardEvent } from 'react';
import { CreateItemDialog } from './create';
import { DeleteItemDialog } from './delete';
import { EditItemDialog } from './edit';

type Props = {
    items: PaginatedDataResponse<Item>;
    filters: { search?: string };
    types: { id: number; name: string }[];
    ppeClassifications: { id: number; name: string }[];
    units: { id: number; name: string; code: string }[];
};

export default function ItemsIndex({ items, filters, types, ppeClassifications, units }: Props) {
    const { data, setData } = useForm({ search: filters.search ?? '' });

    const [createOpen, setCreateOpen] = useState(false);
    const [selected, setSelected] = useState<Item | null>(null);
    const [editOpen, setEditOpen] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);

    const handleEdit = (item: Item) => {
        setSelected(item);
        setEditOpen(true);
    };

    const handleDelete = (item: Item) => {
        setSelected(item);
        setDeleteOpen(true);
    };

    const handleSearchKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        router.get(
            itemsRoutes.index().url,
            data.search ? { search: data.search } : undefined,
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <>
            <Head title="Items" />

            <div className="flex h-full min-w-0 flex-1 flex-col gap-4 rounded-xl p-4">
                <Heading
                    title="Item Management"
                    description="Create, update, and manage items."
                />

                <div className="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <Can permission="items.create">
                        <Button onClick={() => setCreateOpen(true)}>
                            <Plus />
                            Create Item
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
                                <TableHead>Type</TableHead>
                                <TableHead>PPE Classification</TableHead>
                                <TableHead>Unit</TableHead>
                                <TableHead>Unit Cost</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {items.data.length > 0 ? (
                                items.data.map((item) => (
                                    <TableRow key={item.id}>
                                        <TableCell className="font-medium">
                                            {item.item_code}
                                        </TableCell>
                                        <TableCell>{item.item_name}</TableCell>
                                        <TableCell>
                                            {item.type?.name || (
                                                <span className="text-muted-foreground">—</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {item.ppeClassification?.name || (
                                                <span className="text-muted-foreground">—</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {item.unit ? `${item.unit.name} (${item.unit.code})` : (
                                                <span className="text-muted-foreground">—</span>
                                            )}
                                        </TableCell>
                                        <TableCell>
                                            {Number(item.unit_cost).toFixed(2)}
                                        </TableCell>
                                        <TableCell>
                                            {item.is_active ? 'Active' : 'Inactive'}
                                        </TableCell>
                                        <TableCell className="flex items-center justify-end gap-2">
                                            <Can permission="items.update">
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
                                            <Can permission="items.delete">
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
                                    <TableCell colSpan={8} className="h-24 text-center text-muted-foreground">
                                        No items found.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>

                <Pagination data={items} />

                {createOpen && (
                    <CreateItemDialog
                        isOpen={createOpen}
                        onClose={() => setCreateOpen(false)}
                        types={types}
                        ppeClassifications={ppeClassifications}
                        units={units}
                    />
                )}

                {editOpen && selected && (
                    <EditItemDialog
                        isOpen={editOpen}
                        onClose={() => setEditOpen(false)}
                        item={selected}
                        types={types}
                        ppeClassifications={ppeClassifications}
                        units={units}
                    />
                )}

                {deleteOpen && selected && (
                    <DeleteItemDialog
                        isOpen={deleteOpen}
                        onClose={() => setDeleteOpen(false)}
                        item={selected}
                    />
                )}
            </div>
        </>
    );
}

ItemsIndex.layout = {
    breadcrumbs: [
        {
            title: 'Items',
            href: itemsRoutes.index().url,
        },
    ],
};