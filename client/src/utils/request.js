const requestSimple = async (url, data = {}, headers = {}, options = {} ) => {
    options = {
        ...options,
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...headers,
        },
        body: JSON.stringify({...data})
    }

    const response = await fetch(url, options);

    const result = await response.json();

    return result;
};

const request = async (url, data = {}, headers = {} ) => {
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...headers,
        },
        body: JSON.stringify({...data})
    }

    const response = await fetch(url, options);

    return response;
};

export default {
    apiRequestSimple: requestSimple,
    apiRequest: request,
}