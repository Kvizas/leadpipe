import React, { useState } from 'react'
import LeftField from './left-field'

export default function LeftMappingCard({ schema, fieldSource }) {

    return (
        <div className="card">
            <h3>{schema?.title}</h3>
            <p>{schema?.description}</p> {/* Choose which of these form fields will be sent to your CRM and drag them into appropriate CRM fields */}

            <div className="field__wrapper field__wrapper--left">
                {schema?.fields?.map(fieldName =>
                    <LeftField fieldName={fieldName} fieldSource={fieldSource} />
                )}
            </div>
        </div>
    )
}
