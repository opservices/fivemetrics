import React from 'react';
import { Form, Icon, Input, Button, Card, Row, Col } from 'antd';
const FormItem = Form.Item;

class PasswordReset extends React.Component {

    constructor (...props) {
        super(...props);

        this.PASSWORD_ID = 'password';
        this.PASSWORD2_ID = 'password2';
    }

    handleSubmit = (e) => {
        this.props.form.validateFields((err, values) => {

            if (values[this.PASSWORD_ID] != values[this.PASSWORD2_ID]) {
                this.props.form.setFields({
                    password2: {
                        value: values[this.PASSWORD2_ID],
                        errors: [new Error('The password fields must match.')]
                    }
                });
                e.preventDefault();
            }

            if (err) {
                e.preventDefault();
            }
        });
    }

    hasErrors = (fieldsError) => {
        return Object.keys(fieldsError).some(field => fieldsError[field]);
    }

    render() {
        const { getFieldDecorator, getFieldsError } = this.props.form;
        const token = this.props.params.token;

        return (
            <Row type="flex" justify="space-around" align="middle" className="force-middle">
                <Col className='fivemetrics-panel' >


                        <Form className='fivemetrics-antd-theme-form'
                            style={{  margin: "auto" }}
                            action={`/reset-password/${token}`}
                            layout="horizontal"
                            onSubmit={this.handleSubmit}
                            method="POST">
                            <Card title="Change your Password"  style={{ width: 400}}>
                                {/*<h1 style={{textAlign: "center", marginTop: "50%", marginBottom: 50 }}>Change your Password</h1>*/}

                                <PasswordItem id={this.PASSWORD_ID} placeholder='New Password' fieldDecorator={getFieldDecorator} name={this.PASSWORD_ID}/>
                                <PasswordItem id={this.PASSWORD2_ID} placeholder='Confirm Password' fieldDecorator={getFieldDecorator}/>
                            </Card>
                            <FormItem style={{marginTop: 10}}>
                                <Button type="primary" htmlType="submit" disabled={this.hasErrors(getFieldsError())} >
                                    Save
                                </Button>
                            </FormItem>
                        </Form>

                </Col>
          </Row>
       );
    }
}

const PasswordItem = ({message = 'Required Field', ...props}) => {
    return (
        <FormItem>
        {
            props.fieldDecorator(props.id, {rules: [{required: true, message}]})

            (
                <Input
                    prefix={<span className='icon-fontello-key'/>}
                    type="password"
                    placeholder={props.placeholder}
                    onBlur={props.onBlur}
                    name={props.name}
                />
            )
        }
        </FormItem>
    )
}

export default Form.create()(PasswordReset);
