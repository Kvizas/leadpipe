import React, { useState } from 'react'
import PageCRM from '../components/page-crm/page-crm';
import PageForms from '../components/page-forms/page-forms';
import PageGA4 from '../components/page-ga4/page-ga4';
import PageGeneral from '../components/page-general/page-general';

export const AdminNavigations = {
  // "General": <PageGeneral />,
  "Forms": <PageForms />,
  "CRM": <PageCRM />,
  "Google Analytics 4": <PageGA4 />,
  "License": <PageGeneral />,
}

export const AdminNavContext = React.createContext();

export default function AdminNavContextProvider({ children }) {

  const [currentPage, setCurrentPage] = useState(Object.keys(AdminNavigations)[0]);

  return (
    <AdminNavContext.Provider value={{ currentPage, setCurrentPage }}>
      {children}
    </AdminNavContext.Provider>
  )
}
