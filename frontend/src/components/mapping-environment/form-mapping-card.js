import React, { useState, useEffect } from 'react'
import LeftMappingCard from './left-mapping-card'
import { wpGet } from '../../functions/wp-http';
import Loader from '../loader/loader';

export default function FormMappingCard({ formMetadata }) {

    const [formSchema, setFormSchema] = useState({});
    const [isLoading, setIsLoading] = useState(true);

    useEffect(async () => {
        const [status, schema] = await wpGet(`forms/${formMetadata.vendor}/${formMetadata.id}`);
        setFormSchema(() => {
            setIsLoading(false);
            return schema;
        });
    }, [])

    if (isLoading) return <Loader />

    return <LeftMappingCard schema={formSchema} fieldSource="form" />
}
