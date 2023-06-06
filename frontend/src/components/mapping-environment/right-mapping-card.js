import React from 'react'
import RightField from './right-field'
import RightAddField from './right-add-field'

export default function RightMappingCard({ schema }) {

    return (
        <div className="card">
            <h3>{schema?.title}</h3>
            <p className="card__subtitle">
                {schema?.required ?
                    "Please fill all fields marked with a red star."
                    :
                    "Fields marked with a red star are optional as Organization object is optional. If no fields are be provided, the object won't be created in the CRM on form submission. If any fields are provided, the red star fields must be provided as well."
                }
            </p>

            <div className="field__wrapper field__wrapper--right">
                {schema.fields?.map(field =>
                    <RightField data={field} schemaObjTitle={schema?.title} />
                )}
                {
                    schema.customizableFields ?
                        <RightAddField schemaObjKey={schema?.key} />
                        :
                        <></>
                }
            </div>
        </div>
    )
}
