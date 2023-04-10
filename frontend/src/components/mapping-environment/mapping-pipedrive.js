import React, { useState, useEffect, useContext } from 'react'
import RightMappingCard from './right-mapping-card'
import { wpGet } from '../../functions/wp-http';
import Notice from "../notice/notice";
import Loader from '../loader/loader';
import { SchemaContext } from '../../contexts/schema';

export default function MappingPipedrive({ formMetadata }) {

    const { schema, setSchema } = useContext(SchemaContext);

    const [isLoading, setIsLoading] = useState(true);
    const [errorMsg, setErrorMsg] = useState();

    useEffect(async () => {
        const [status, data] = await wpGet(`crms/${formMetadata.vendor}/${formMetadata.id}`);

        if (status == 200)
            setSchema(() => {
                setIsLoading(false);
                return data;
            });
        else setErrorMsg("Error: " + data.message);
    }, [])

    if (isLoading) return <Loader />

    if (errorMsg)
        return <Notice type="error">{errorMsg}</Notice>

    return schema.map(schemaObj => <RightMappingCard schema={schemaObj} />)
}
