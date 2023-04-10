import React, { useState } from 'react'
import uuid from 'react-uuid';

import { useDraggable } from '@dnd-kit/core';

import "./field.sass"

export default function LeftField({ fieldName, fieldSource }) {

    const [id,] = useState(uuid());

    const { attributes, listeners, setNodeRef, transform, isDragging } = useDraggable({
        id: 'draggable-' + id,
        data: {
            fieldName,
            fieldSource
        }
    });

    const style = transform ? {
        transition: "transition: background .2s, transform .2s, opacity .2s",
        transform: `translate3d(${transform.x}px, ${transform.y}px, 0)`,
    } : undefined;

    const className = "field field__left" + (isDragging ? " field--dragging" : "");

    return (
        <div ref={setNodeRef} {...listeners} {...attributes} className={className} style={style}>
            {fieldName}
        </div>
    )
}
