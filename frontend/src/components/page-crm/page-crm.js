import React, { useState, useEffect } from 'react'
import { wpGet, wpPost } from '../../functions/wp-http';
import Notice from '../notice/notice';
import Loader from '../loader/loader';

export default function PageCRM() {

    const [isLoading, setIsLoading] = useState(true);
    const [allCRMs, setAllCRMs] = useState({});
    const [selectedCRM, setSelectedCRM] = useState({});
    const [authFields, setAuthFields] = useState({});

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [successMsg, setSuccessMsg] = useState();
    const [errorMsg, setErrorMsg] = useState();

    useEffect(() => {
        async function loadCRMs() {
            const [status, data] = await wpGet(`crms`);
            setAllCRMs(data);
            setSelectedCRM(data.current);
            setAuthFields(data.current?.authData || {});
            setIsLoading(false);
        }

        loadCRMs();
    }, []);

    const onSelect = event => {
        const name = event.target.value;
        setSelectedCRM(allCRMs.all[name]);

        let _authFields = {};
        if (allCRMs.current.name == name)
            _authFields = allCRMs.current.authData;
        else
            allCRMs.all[name].authFields.forEach(field => _authFields[field] = "");

        setAuthFields(_authFields);
    }

    const handleChange = (field, e) => {
        const value = e.target.value;

        setAuthFields(previous => {
            const newFields = { ...previous };
            newFields[field] = value;
            return newFields;
        });
    }

    const handleSubmit = async () => {

        setIsSubmitting(true);

        const [status, data] = await wpPost(`crms`, {
            name: selectedCRM.name,
            authData: authFields
        });

        if (status == 200)
            setSuccessMsg(`${selectedCRM.name} successfully authenticated.`);
        else
            setErrorMsg(data.message);

        setIsSubmitting(false);

    }


    return (
        <>
            <h2>Customer Relationship Management settings</h2>
            {
                isLoading ?
                    <Loader />
                    :
                    <form>
                        <table className='form-table'>
                            <tbody>
                                <tr class="user-first-name-wrap">
                                    <th><label for="current">Choose your CRM</label></th>
                                    <td>
                                        <select onChange={onSelect} name="current" id="current" class="regular-text">
                                            {Object.keys(allCRMs.all).map(crm =>
                                                <option key={crm} selected={crm == selectedCRM.name} value={crm}>{crm}</option>
                                            )}
                                        </select>
                                    </td>
                                </tr>
                                {
                                    Object.keys(authFields).map(field =>
                                        <tr key={field} class="user-first-name-wrap">
                                            <th><label for={field}>{field}</label></th>
                                            <td>
                                                <input
                                                    type="text"
                                                    name={field}
                                                    value={authFields[field]}
                                                    onChange={e => handleChange(field, e)}
                                                    id={field}
                                                    class="regular-text"
                                                />
                                            </td>
                                        </tr>
                                    )
                                }
                            </tbody>
                        </table>
                        <div style={{ display: "flex" }}> {/* is-layout-flex broken due to wordpress 6.1 update */}
                            <div onClick={handleSubmit} class="button button-primary">Submit</div>
                            {
                                isSubmitting ?
                                    <Notice>Submitting...</Notice>
                                    :
                                    successMsg ?
                                        <Notice type="success">{successMsg}</Notice>
                                        :
                                        errorMsg ?
                                            <Notice type="error">Error: {errorMsg}</Notice>
                                            :
                                            <></>
                            }
                        </div>
                    </form>
            }
        </>
    )
}
