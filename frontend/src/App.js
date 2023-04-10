import React from 'react';
import { PageView } from './components/page-view/page-view';
import Navbar from './components/navbar/navbar';
import AdminNavContextProvider from './contexts/admin-nav';

const App = () => {
    return (
        <div>
            <h2 style={{ fontSize: "1.5rem" }}>Leadpipe Management</h2>
            {/* <hr /> */}
            <AdminNavContextProvider>
                <Navbar></Navbar>
                <PageView />
            </AdminNavContextProvider>
        </div>
    );
}

export default App; 