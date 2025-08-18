import { useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { FaEye } from "react-icons/fa";
import { Button } from "@/components/ui/button";
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationLink,
  PaginationPrevious,
  PaginationNext,
} from "@/components/ui/pagination";

/**
 * @description Component to see the details of the movements of an ingredient
 * 
 */
export default function Index({ data, ingredient, stock }) {
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 3;

  const totalPages = Math.ceil(data.length / itemsPerPage);

  const currentData = data.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  const handlePageChange = (page) => {
    if (page >= 1 && page <= totalPages) {
      setCurrentPage(page);
    }
  };

  return (
    <Dialog>
      <DialogTrigger>
        <Button variant="customGreen" size="icon">
          <FaEye />
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>History - {ingredient}</DialogTitle>
          <DialogDescription>Stock: {stock}</DialogDescription>
        </DialogHeader>
        <Table>
          <TableCaption>Movements list</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead className="w-[100px]">Quantity</TableHead>
              <TableHead className="text-right">Type</TableHead>
              <TableHead className="text-right">Date</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {currentData.map((item, index) => (
              <TableRow key={index}>
                <TableCell className="font-medium">{item.quantity}</TableCell>
                <TableCell className="text-right font-bold">{item.type}</TableCell>
              <TableCell className="text-right font-bold">{new Date(item.created_at).toLocaleString()}</TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
        <Pagination className="flex justify-center mt-4">
          <PaginationContent>
            <PaginationItem>
              <PaginationPrevious
              className="cursor-pointer"
                onClick={() => handlePageChange(currentPage - 1)}
                disabled={currentPage === 1}
              />
            </PaginationItem>
            {Array.from({ length: totalPages }, (_, index) => (
              <PaginationItem key={index} >
                <PaginationLink
                  onClick={() => handlePageChange(index + 1)}
                  active={currentPage === index + 1}
                >
                  {index + 1}
                </PaginationLink>
              </PaginationItem>
            ))}
            <PaginationItem>
              <PaginationNext
              className="cursor-pointer"

                onClick={() => handlePageChange(currentPage + 1)}
                disabled={currentPage === totalPages}
              />
            </PaginationItem>
          </PaginationContent>
        </Pagination>
      </DialogContent>
    </Dialog>
  );
}
