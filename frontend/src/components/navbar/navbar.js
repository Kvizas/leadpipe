import React, { useContext } from 'react'
import { AdminNavContext, AdminNavigations } from '../../contexts/admin-nav'

export default function Navbar() {

    const { currentPage, setCurrentPage } = useContext(AdminNavContext);

    return (
        <h2 className='nav-tab-wrapper'>
            {Object.keys(AdminNavigations).map(
                name => <a
                    className={'nav-tab' + (currentPage == name ? ' nav-tab-active' : '')}
                    onClick={() => setCurrentPage(name)}
                    href="#"
                >{name}</a>
            )}
        </h2>
    )
}
