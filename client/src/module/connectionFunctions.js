
export const fetchData = async (requestType = 'GET', url, data = {}) => {

    let options = {
        method: requestType,
    }

    if (requestType != 'GET') {
        options = {
            ...options,
            body: JSON.stringify(data)
        }
    }

    try {
        let res = await fetch(url, options).catch((e) => { return false });

        if (!res) {
            return {
                data: false,
                message: 'Network error!'
            };
        }

        let result = await res.json();
        //console.log(result);
        
        if (res.status >= 200 && res.status < 300) {
            return {
                data: result.data,
                message: ''
            };
        } else {

            return {
                data: false,
                message: result.message
            };
        }

    } catch (err) {
        //console.log(err);
    }

};