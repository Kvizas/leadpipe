import React, { useState } from 'react'

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

    return (
        <SchemaContext.Provider value={{ schema, setSchema, changeSchemaField }}>
            {children}
        </SchemaContext.Provider>
    )
}
