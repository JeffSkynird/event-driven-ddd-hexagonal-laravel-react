import Sidebar from './Sidebar';
import { ModeToggle } from './mode-toggle';
import { Outlet } from 'react-router-dom';
import { Toaster } from './ui/toaster';

export default  function Layout() {
    return (
      <div className="container2">
        <Sidebar />
        <main className="content">
          <div className="absolute top-2 right-2">
            <ModeToggle />
          </div>
          <Outlet />
        </main>
        <Toaster />
      </div>
    );
}
