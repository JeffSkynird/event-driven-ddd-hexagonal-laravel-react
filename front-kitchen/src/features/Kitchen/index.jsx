import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { ToastAction } from "@radix-ui/react-toast";
import { useToast } from "@/hooks/use-toast";
import { prepareOrder } from "@/service/api";

/**
 * @description Component to order a dish
 * 
 */
export default function index() {
  const { toast } = useToast()
  const handleButtonClick = async() => {
    try{
      const data = await prepareOrder();
      toast({
        title: 'Creating order',
        description: data.message,
        action: (
          <ToastAction altText="Goto orders" onClick={()=> window.location.href = "/orders"}>See orders</ToastAction>
        ),
      })
    }catch(e){
      toast({
        title: 'Error',
        description: e.message
   
      })
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>Request order</CardTitle>
        <CardDescription>Get a random dish</CardDescription>
      </CardHeader>
      <CardContent>
        <Alert>
          <AlertTitle>Randon dish</AlertTitle>
          <AlertDescription>
            Click and send the order to the kitchen
          </AlertDescription>
        </Alert>
      </CardContent>
      <CardFooter>
        <Button variant="customGreen" onClick={handleButtonClick}>Send order!</Button>
      </CardFooter>
    </Card>
  );
}
