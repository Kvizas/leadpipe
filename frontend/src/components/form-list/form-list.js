import React, { useEffect, useState, useContext } from 'react'
import { AdminNavContext } from '../../contexts/admin-nav';
import { wpGet } from '../../functions/wp-http';
import PageFormMapper from '../page-form-mapper/page-form-mapper';
import Loader from '../loader/loader';

export default function FormList() {

    const [isLoading, setIsLoading] = useState(true);
    const [formsData, setFormsData] = useState({});

    useEffect(() => {
        async function loadData() {
            const [status, data] = await wpGet('forms');
            setFormsData(() => {
                setIsLoading(false)
                return data;
            });
        }

        loadData();
    }, []);


    const { setCurrentPage } = useContext(AdminNavContext);

    const goToForm = (formMetadata) => {
        setCurrentPage({
            pageComponent: PageFormMapper,
            pageState: {
                formMetadata
            }
        })

    }

    return (
        <table className='wp-list-table widefat fixed striped table-view-list posts'>
            <thead>
                <tr>
                    <th className="manage-column column-title" id="title" scope="col">Title</th>
                    <th className="manage-column column-vendor" id="vendor" scope="col">Forms Plugin (Vendor)</th>
                    <th className="manage-column column-form-id" id="form-id" scope="col">Form ID</th>
                </tr>
            </thead>
            <tbody>
                {isLoading ?
                    <Loader />
                    :
                    Object.keys(formsData).map(vendor => {
                        return Object.values(formsData[vendor]).map(form =>
                            <tr>
                                <td class="title column-title has-row-actions column-primary" data-colname="Title">
                                    <strong><a class="row-title" href="#" onClick={() => goToForm(form)} aria-label={"Set mapping for " + form.title}>{form.title}</a></strong>
                                </td>
                                <td class="vendor column-vendor" data-colname="Vendor">{vendor}</td>
                                <td class="form-id column-form-id" data-colname="Vendor">{form.id}</td>
                            </tr>
                        )
                    }
                    )}
            </tbody>
        </table>
    )
}
