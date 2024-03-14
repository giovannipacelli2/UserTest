import React from 'react';
import './User.scss';

const User = ({data}) => {

    const { name, surname, email, birthDate } = data;

  return (
    <div className='user-container'>
        <div className="elem">{name}</div>
        <div className="elem">{surname}</div>
        <div className="elem">{email}</div>
        <div className="elem">{birthDate}</div>
    </div>
  )
}

User.defaultProps = {
    data : {
        name: 'nome',
        surname: 'cognome',
        email: 'email',
        birthDate: 'data',
    }
}

export default User