"use client";

import {
  flexRender,
  getCoreRowModel,
  useReactTable,
  getPaginationRowModel,
} from "@tanstack/react-table";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Button } from "../ui/button";

export function DataTable({
  columns,
  data,
  total,
  currentPage,
  perPage,
  setCurrentPage,
  setPerPage,
  loading = false,
}) {
  console.log(data)
  const pageCount = Math.ceil(total / perPage); // Número total de páginas

  const table = useReactTable({
    data,
    columns,
    pageCount, // Total de páginas
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    manualPagination: true, // Paginas manualmente
    state: {
      pagination: {
        pageIndex: currentPage, // Página actual
        pageSize: perPage, // Tamaño de página
      },
    },
    onPaginationChange: (newState) => {
      if (newState?.pagination) {
        setCurrentPage(newState.pagination.pageIndex); // Cambiar página
        setPerPage(newState.pagination.pageSize); // Cambiar tamaño de página
      }
    },
  });

  return (
    <div>
      <div className="rounded-md border">
        
        <Table >
          <TableHeader>
            {table.getHeaderGroups().map((headerGroup) => (
              <TableRow key={headerGroup.id}>
                {headerGroup.headers.map((header) => {
                  return (
                    <TableHead key={header.id}>
                      {header.isPlaceholder
                        ? null
                        : flexRender(
                            header.column.columnDef.header,
                            header.getContext()
                          )}
                    </TableHead>
                  );
                })}
              </TableRow>
            ))}
          </TableHeader>
          <TableBody>
            {loading ? (
              <TableRow>
                <TableCell colSpan={columns.length} className="h-24 text-center">
                  Loading...
                </TableCell>
              </TableRow>
            ) : table.getRowModel().rows?.length ? (
              table.getRowModel().rows.map((row) => (
                <TableRow key={row.id}>
                  {row.getVisibleCells().map((cell) => (
                    <TableCell key={cell.id}>
                      {flexRender(
                        cell.column.columnDef.cell,
                        cell.getContext()
                      )}
                    </TableCell>
                  ))}
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell
                  colSpan={columns.length}
                  className="h-24 text-center"
                >
                  No results.
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>

      {/* Controles de paginación */}
      <div className="flex items-center justify-between space-x-2 mt-4">
        <div className="flex items-center space-x-2">
          <p className="text-sm font-medium">Rows per page</p>
          <Select
            value={`${table.getState().pagination.pageSize}`}
            onValueChange={(value) => {
              setPerPage(Number(value));
            }}
          >
            <SelectTrigger className="h-8 w-[70px]">
              <SelectValue placeholder={table.getState().pagination.pageSize} />
            </SelectTrigger>
            <SelectContent side="top">
              {[5, 10, 20].map((pageSize) => (
                <SelectItem key={pageSize} value={`${pageSize}`}>
                  {pageSize}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="flex w-[100px] items-center justify-center text-sm font-medium">
          Page {currentPage + 1} of {pageCount}
        </div>
      </div>

      <div className="flex items-center justify-end space-x-2 py-2">
        <Button
          variant="outline"
          size="sm"
          onClick={() => {
            if (currentPage > 0) setCurrentPage(currentPage - 1);
          }}
          disabled={currentPage === 0} // Deshabilitar si estás en la primera página
        >
          Previous
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => {
            if (currentPage < pageCount - 1) setCurrentPage(currentPage + 1);
          }}
          disabled={currentPage >= pageCount - 1} // Deshabilitar si estás en la última página
        >
          Next
        </Button>
      </div>
    </div>
  );
}
