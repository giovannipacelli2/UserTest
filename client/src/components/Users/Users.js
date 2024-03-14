import React, { useEffect } from 'react';
import './Users.scss';

import { useGlobalContext } from '../../context';

// Import components

import User from '../User/User';

const Users = () => {

    const { users, fetchUsers } = useGlobalContext();

    // fetch users
    useEffect(() => {
        fetchUsers();
    }, []);

    return (
        <div className="container">
            <div className='users-container'>
                {
                    users.map((user, index)=>{
                        return <User data={user} key={index}/>
                    })
                }
            </div>
        </div>
    )
}

export default Users