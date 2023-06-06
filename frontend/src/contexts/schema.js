import React, { useState } from 'react'
import slugify from 'react-slugify'

import wpPost from "../functions/wp-http";

export const SchemaContext = React.createContext();

export default function SchemaContextProvider({ children }) {

    const [schema, setSchema] = useState([]);

    const changeSchemaField = (objTitle, key, source, valueField) => {
        setSchema(prev => {
            prev.forEach(schemaObj => {

                if (schemaObj.title == objTitle)
                    return schemaObj.fields.forEach(schemaField => {

                        if (schemaField.key == key) {
                            schemaField.source = source;
                            schemaField.valueField = valueField;
                            return;
                        }

                    })
            });
            return prev.slice();
        });
    }

    const addCustomField = (objectKey, label) => {

        const key = slugify(label);

        const isKeyAvailable = fields => {
            fields.forEach(field => {
                if (field.key == key) throw Error("Field with a similar name already exists.");
            });
        }

        const sendAddFieldRequest = async () => {

            const resp = await wpPost("crms/add_field", { objectKey, label });

            // TODO

        }

        setSchema(prev => {
            prev.forEach(schemaObj => {
                if (schemaObj.key == objectKey) {
                    isKeyAvailable(schemaObj.fields);

                    sendAddFieldRequest()

                    schemaObj.fields.push({
                        "label": label,
                        "key": key,
                        "required": false,
                        "deletable": true,
                        "source": "",
                        "valueField": ""
                    });
                    return;
                }

            });
            return prev.slice();
        });
    }

    return (
        <SchemaContext.Provider value={{ schema, setSchema, changeSchemaField, addCustomField }}>
            {children}
        </SchemaContext.Provider>
    )
}
