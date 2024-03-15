import React, {useState} from 'react';
import './UserForm.scss';

import { useGlobalContext } from '../../context';

const UserForm = () => {
	
	const { createUser, fetchUsers } = useGlobalContext();

	const initialData = {
		name : '',
		surname : '',
		email : '',
		birthDate : ''
	};

	const [ data, setData ] = useState(initialData);
	
	
	const handleChange = (e) => {
		
		if (!e.target) return;
		
		let name = e.target.name;
		let value = e.target.value;
		
		setData((prevState)=>{
			return {
				...prevState,
				[name] : value
			};
		});
	};
	
	const handleSubmit = async (e) => {

		e.preventDefault();
		
		let res = await createUser(data);

		if (!res) {
			console.log('error sending data');
		} else {
			await fetchUsers();
		}
		
		setData(initialData);
	};

		
		  return (
			<form onSubmit={handleSubmit} className='user-form'>
				<input
					type='text'
					id='name'
					name='name'
					value={data.name}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='text'
					id='surname'
					name='surname'
					value={data.surname}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='email'
					id='email'
					name='email'
					value={data.email}
					onChange={(e)=>{handleChange(e)}}
				/>
				<input
					type='date'
					id='birthDate'
					name='birthDate'
					value={data.birthDate}
					onChange={(e)=>{handleChange(e)}}
				/>
				<div className="btn-container">
					<button type="submit">Save</button>
				</div>
			</form>
  )


}

export default UserForm