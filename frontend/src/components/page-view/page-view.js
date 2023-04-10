import React, { useContext } from 'react'
import { AdminNavContext, AdminNavigations } from '../../contexts/admin-nav'

export const PageView = () => {

    const { currentPage } = useContext(AdminNavContext);

    if (typeof currentPage == "string") return AdminNavigations[currentPage];

    // Custom pages with statuses below

    const pageComponent = currentPage.pageComponent;
    const pageState = currentPage.pageState;

    return React.createElement(pageComponent, pageState)

}