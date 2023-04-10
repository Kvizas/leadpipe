import React, { useState, useContext } from 'react'
import { SchemaContext } from '../../contexts/schema';
import { wpPost } from '../../functions/wp-http';
import Notice from '../notice/notice';

export default function MappingEnvironmentFooter({ formMetadata }) {

    const { schema } = useContext(SchemaContext);

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errorMsg, setErrorMsg] = useState("");
    const [successMsg, setSuccessMsg] = useState("");

    const submitMapping = async () => {

        setIsSubmitting(true);
        setErrorMsg("");
        setSuccessMsg("");

        const [status, data] = await wpPost(`crms/${formMetadata.vendor}/${formMetadata.id}`, schema);

        if (status == 200)
            setSuccessMsg("Saved successfully.");
        else
            setErrorMsg(() => {
                setIsSubmitting(false);
                return data.message;
            })

        setIsSubmitting(false);

    }

    return (
        <div className="mapEnv__footer">
            {
                isSubmitting ?
                    <Notice>Submitting...</Notice>
                    :
                    errorMsg ?
                        <Notice type='error'>{errorMsg}</Notice>
                        :
                        successMsg ?
                            <Notice type='success'>{successMsg}</Notice>
                            :
                            <></>
            }
            <input
                onClick={submitMapping}
                type="submit"
                name="submit"
                id="submit"
                class="button button-primary"
                value="Save Changes"
                disabled={isSubmitting}
            />
        </div>
    )
}
