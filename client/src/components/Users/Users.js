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
                <div className="user-container head">
                    <div className="elem">Nome</div>
                    <div className="elem">Cognome</div>
                    <div className="elem">Email</div>
                    <div className="elem">Data di nascita</div>
                </div>
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