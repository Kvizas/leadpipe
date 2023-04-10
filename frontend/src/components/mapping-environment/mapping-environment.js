import React, { useContext } from 'react'

import "./mapping-environment.sass";
import MappingPipedrive from './mapping-pipedrive';
import LeftMappingCard from './left-mapping-card';

import { DndContext } from '@dnd-kit/core';
import MappingEnvironmentFooter from './mapping-env-footer';
import FormMappingCard from './form-mapping-card';
import { SchemaContext } from '../../contexts/schema';

export default function MappingEnvironment({ formMetadata }) {

    const { changeSchemaField } = useContext(SchemaContext);

    const handleDragEnd = event => {

        const leftData = event.active.data.current;
        const rightData = event.over.data.current;

        changeSchemaField(
            rightData.schemaObjTitle,
            rightData.fieldData.key,
            leftData.fieldSource,
            leftData.fieldName
        );

    }

    return (
        <div className='mapEnv'>
            <DndContext onDragEnd={handleDragEnd}>
                <div>
                    <FormMappingCard formMetadata={formMetadata} />
                    <LeftMappingCard schema={{ title: "Internal variables", fields: ["GA4 User ID"] }} fieldSource={"internal"} />
                </div>
                <div>
                    <MappingPipedrive formMetadata={formMetadata} />
                </div>
            </DndContext>
            <MappingEnvironmentFooter formMetadata={formMetadata} />
        </div>
    )
}
