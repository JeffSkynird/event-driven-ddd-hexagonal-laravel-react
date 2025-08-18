import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Button } from "@/components/ui/button";
/**
 * @description Component for the Home page
 */
export default function index() {
  const handleButtonClick = () => {
    window.location.href = "/kitchen"; 
  };
  return (
    <Card>
      <CardHeader>
        <CardTitle>Technical Test</CardTitle>
        <CardDescription>Welcome to my app </CardDescription>
      </CardHeader>
      <CardContent>
        <p>I hope you like it :) </p>
      </CardContent>
      <CardFooter>
        <Button variant="customGreen" onClick={handleButtonClick} >Get Started!</Button>
      </CardFooter>
    </Card>
  );
}
