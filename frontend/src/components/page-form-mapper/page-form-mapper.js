import React from 'react'
import MappingEnvironment from '../mapping-environment/mapping-environment'
import SchemaContextProvider from '../../contexts/schema'

export default function PageFormMapper({ formMetadata }) {
    return (
        <SchemaContextProvider>
            <MappingEnvironment formMetadata={formMetadata} />
        </SchemaContextProvider>
    )
}
