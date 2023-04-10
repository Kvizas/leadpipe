import React, { useState, useContext } from 'react'
import uuid from 'react-uuid';
import { useDroppable } from '@dnd-kit/core';

import "./field.sass"
import { SchemaContext } from '../../contexts/schema';

export default function RightField({ data, schemaObjTitle }) {

    const [id,] = useState(uuid());

    const { changeSchemaField } = useContext(SchemaContext);

    const { isOver, setNodeRef } = useDroppable({
        id: 'droppable-' + id,
        data: {
            fieldData: data,
            schemaObjTitle
        }
    });

    const className = "field field__right" + (isOver ? " field--dropping" : "");


    const onRemove = () => {
        changeSchemaField(schemaObjTitle, data.key, "", "");
    }

    const onDelete = () => {



    }


    return (
        <>
            <div className={"field__label" + (data.required ? " field__label--required" : "")}>
                {data.label}
            </div>
            <div ref={setNodeRef}>
                {
                    data.valueField ?
                        <div className="field field__left" style={{ width: "-webkit-fill-available" }}>
                            {data.valueField}
                        </div>
                        :
                        <div className={className}>
                            Drag one of the form fields here.
                        </div>
                }
            </div>
            {
                data.valueField ?
                    <div className='field__edit field__remove' onClick={onRemove}>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.83317 9.16602V10.8327H14.1665V9.16602H5.83317ZM9.99984 1.66602C5.39984 1.66602 1.6665 5.39935 1.6665 9.99935C1.6665 14.5993 5.39984 18.3327 9.99984 18.3327C14.5998 18.3327 18.3332 14.5993 18.3332 9.99935C18.3332 5.39935 14.5998 1.66602 9.99984 1.66602ZM9.99984 16.666C6.32484 16.666 3.33317 13.6743 3.33317 9.99935C3.33317 6.32435 6.32484 3.33268 9.99984 3.33268C13.6748 3.33268 16.6665 6.32435 16.6665 9.99935C16.6665 13.6743 13.6748 16.666 9.99984 16.666Z" fill="black" />
                        </svg>
                    </div>
                    :
                    data.deletable ?
                        <div className='field__edit field__remove' onClick={onDelete}>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.4824 3.75H13.125V1.875C13.125 1.70924 13.0592 1.55027 12.9419 1.43306C12.8247 1.31585 12.6658 1.25 12.5 1.25H7.5C7.33424 1.25 7.17527 1.31585 7.05806 1.43306C6.94085 1.55027 6.875 1.70924 6.875 1.875V3.75H2.51758L2.5 5.3125H3.78906L4.57383 17.5781C4.59369 17.8953 4.73363 18.193 4.96518 18.4107C5.19673 18.6284 5.5025 18.7497 5.82031 18.75H14.1797C14.4973 18.75 14.803 18.629 15.0347 18.4117C15.2664 18.1944 15.4066 17.8971 15.427 17.5801L16.2109 5.3125H17.5L17.4824 3.75ZM6.875 16.25L6.52344 6.25H7.8125L8.16406 16.25H6.875ZM10.625 16.25H9.375V6.25H10.625V16.25ZM11.5625 3.75H8.4375V2.65625C8.4375 2.61481 8.45396 2.57507 8.48326 2.54576C8.51257 2.51646 8.55231 2.5 8.59375 2.5H11.4062C11.4477 2.5 11.4874 2.51646 11.5167 2.54576C11.546 2.57507 11.5625 2.61481 11.5625 2.65625V3.75ZM13.125 16.25H11.8359L12.1875 6.25H13.4766L13.125 16.25Z" fill="black" />
                            </svg>
                        </div>
                        :
                        <div className='field__edit'></div>
            }
        </>
    )
}
