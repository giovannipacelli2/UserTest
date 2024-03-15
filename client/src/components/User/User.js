import React, {useState, useEffect} from 'react';
import './User.scss';

import { useGlobalContext } from '../../context';

const User = ({data}) => {
	
	const { users, deleteUser, editUser, fetchUsers } = useGlobalContext();

    const { id, name, surname, email, birthDate } = data;
	
	const [ isEdit, setIsEdit ] = useState(false);
	const [ editData, setEditData ] = useState({});
	
	useEffect(()=>{
		setEditData({
			name, surname, email, birthDate
		});
	}, [users]);
	
	const handleChange = (e) => {
		
		if (!e.target) return;
		
		let name = e.target.name;
		let value = e.target.value;
		
		setEditData((prevState)=>{
			return {
				...prevState,
				[name] : value
			};
		});
	};
	
	const handleSubmit = async (e) => {

		e.preventDefault();
		
		let res = await editUser(editData, id);
		console.log(res);
		if (!res) {
			console.log('error in edit');
		} else {
			await fetchUsers();
		}
		
		setIsEdit(false);
		setEditData({})
	};
	
	if (!isEdit) {
		
		  return (
			<div className='user-container'>
				<div className="elem">{name}</div>
				<div className="elem">{surname}</div>
				<div className="elem">{email}</div>
				<div className="elem">{birthDate}</div>
				<div className="btn-container">
					<button type="button" onClick={()=>{setIsEdit(true)}}>Edit</button>
					<button type="button" onClick={()=>{deleteUser(id)}}>Delete</button>
				</div>
			</div>
		  )
	} else {
		
		  return (
			<form onSubmit={handleSubmit} className='user-form'>
				<input
					type='text'
					id='name'
					name='name'
					value={editData.name}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='text'
					id='surname'
					name='surname'
					value={editData.surname}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='email'
					id='email'
					name='email'
					value={editData.email}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='date'
					id='birthDate'
					name='birthDate'
					value={editData.birthDate}
					onChange={(e)=>{handleChange(e)}}
				/>
				<div className="btn-container">
					<button type="submit">Save</button>
				</div>
			</form>
  )
	}


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